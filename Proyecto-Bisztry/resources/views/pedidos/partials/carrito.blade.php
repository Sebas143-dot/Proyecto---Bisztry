{{-- Este es el componente reutilizable para el carrito de compras --}}
<div class="card sticky-card">
    <div class="card-header"><h3><i class="fas fa-shopping-cart"></i> Carrito de Compra</h3></div>
    <div class="card-body">
        @if(empty($carrito))
            <div class="empty-state">
                <i class="fas fa-cart-arrow-down"></i>
                <p>Añade productos desde el catálogo.</p>
            </div>
        @else
            @php $subtotal = 0; @endphp
            <ul class="carrito-list">
                @foreach($carrito as $id => $item)
                    @php $subtotal += $item['subtotal']; @endphp
                    <li>
                        <div class="flex-grow">
                            <span class="font-bold">{{ $item['nombre'] }}</span>
                            <span class="block text-secondary text-small">{{ $item['cantidad'] }} uds. x ${{ number_format($item['precio'], 2) }}</span>
                        </div>
                        <strong class="text-primary">${{ number_format($item['subtotal'], 2) }}</strong>
                        <form action="{{ route('pedidos.cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="variante_id" value="{{ $id }}">
                            <button type="submit" class="btn-icon-danger-sm" title="Quitar del carrito">&times;</button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <hr class="my-4">
            <div class="total-line">
                <span>Subtotal:</span>
                <strong>${{ number_format($subtotal, 2) }}</strong>
            </div>
        @endif
        
        @if(!empty($carrito))
        <div class="mt-4">
            <a href="{{ route('pedidos.create.step3') }}" class="btn btn-primary w-full justify-center">
                Siguiente: Completar Pedido <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        @endif
    </div>
</div>
<style>
.sticky-card { position: sticky; top: 2rem; }
.carrito-list { list-style: none; }
.carrito-list li { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color); }
.carrito-list li:last-child { border-bottom: none; }
.text-small { font-size: 0.8rem; }
.btn-icon-danger-sm { background:none; border:none; color:var(--danger-color); font-weight:bold; cursor:pointer; font-size: 1.5rem; line-height: 1; }
.total-line { display: flex; justify-content: space-between; font-weight: 600; font-size: 1.1rem; }
.w-full { width: 100%; }
.ml-2 { margin-left: 0.5rem; }
</style>
