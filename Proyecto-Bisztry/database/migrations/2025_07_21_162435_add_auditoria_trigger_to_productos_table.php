<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            -- Creamos una FUNCIÓN GENÉRICA para registrar cambios.
            CREATE OR REPLACE FUNCTION registrar_cambio_generico()
            RETURNS TRIGGER AS $$
            DECLARE
                -- Declaramos variables que usaremos dentro de la función.
                campo TEXT;
                valor_antiguo TEXT;
                valor_nuevo TEXT;
                fila_antigua_json JSONB; -- PostgreSQL tiene un tipo de dato JSON muy potente.
                fila_nueva_json JSONB;
            BEGIN
                -- `OLD` y `NEW` son variables especiales en los triggers.
                -- `OLD` contiene todos los datos de la fila ANTES de la actualización.
                -- `NEW` contiene todos los datos de la fila DESPUÉS de la actualización.
                -- Convertimos ambas a formato JSONB para poder manipularlas fácilmente.
                fila_antigua_json := to_jsonb(OLD);
                fila_nueva_json := to_jsonb(NEW);

                -- `jsonb_object_keys` extrae los nombres de todas las columnas (ej: "prod_nombre", "prod_estado").
                -- Este bucle `FOR` recorrerá cada una de esas columnas.
                FOR campo IN SELECT jsonb_object_keys(fila_nueva_json)
                LOOP
                    -- `->>` es un operador de JSON que extrae el valor de una clave como texto.
                    valor_antiguo := fila_antigua_json ->> campo;
                    valor_nuevo := fila_nueva_json ->> campo;

                    -- `IS DISTINCT FROM` es una forma segura de comparar si dos valores son diferentes,
                    -- incluso si uno de ellos es nulo (NULL).
                    -- También excluimos la columna `updated_at` para no registrar cambios automáticos.
                    IF valor_nuevo IS DISTINCT FROM valor_antiguo AND campo <> \'updated_at\' THEN
                        -- Si el valor ha cambiado, insertamos una nueva fila en nuestra tabla de auditoría.
                        INSERT INTO auditoria_productos(
                            prod_cod_afectado, 
                            usuario_modificador, 
                            campo_modificado, 
                            valor_antiguo, 
                            valor_nuevo
                        )
                        VALUES(
                            OLD.prod_cod,       -- El ID del producto que cambió.
                            current_user,       -- El usuario de la base de datos que hizo el cambio.
                            campo,              -- El nombre de la columna que cambió.
                            valor_antiguo,      -- El valor que tenía antes.
                            valor_nuevo         -- El nuevo valor.
                        );
                    END IF;
                END LOOP;

                -- Es obligatorio que una función de trigger devuelva NEW.
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            -- Creamos el DISPARADOR (TRIGGER) que vigila la tabla.
            CREATE TRIGGER trg_auditoria_productos
            BEFORE UPDATE ON productos -- Se activa ANTES de que se guarde una actualización en la tabla `productos`.
            FOR EACH ROW -- Se ejecuta por cada fila que sea actualizada.
            EXECUTE FUNCTION registrar_cambio_generico(); -- Llama a nuestra función "cerebro".
        ');
    }

    public function down(): void
    {
        // Este código permite deshacer los cambios de forma segura si ejecutas `migrate:rollback`.
        DB::unprepared('
            DROP TRIGGER IF EXISTS trg_auditoria_productos ON productos;
            DROP FUNCTION IF EXISTS registrar_cambio_generico();
        ');
    }
};
