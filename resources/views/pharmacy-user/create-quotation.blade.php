@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

<style>
.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.swiper {
    width: 100%;
    height: 400px;
    margin-left: auto;
    margin-right: auto;
}

.swiper-slide {
    background-size: cover;
    background-position: center;
}

.mySwiper2 {
    height: 350px;
    width: 100%;
}

.mySwiper {
    height: 20%;
    box-sizing: border-box;
    padding: 10px 0;
}

.mySwiper .swiper-slide {
    width: 25%;
    height: 100%;
    opacity: 0.4;
}

.mySwiper .swiper-slide-thumb-active {
    opacity: 1;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create Quotation</div>

                <div class="card-body row">
                    <div class="col-md-6">
                        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                            class="swiper mySwiper2">
                            <div class="swiper-wrapper">
                                @foreach($prescriptionImages as $image)
                                <div class="swiper-slide">
                                    <img src="{{asset($image->image_url)}}" />
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- <div thumbsSlider="" class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @foreach($prescriptionImages as $image)
                                <div class="swiper-slide">
                                    <img src="{{asset($image->image_url)}}" />
                                </div>
                                @endforeach
                            </div>
                        </div> -->
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <table class="table table-striped">
                            <thead>
                                <th>Drug</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="cart-body">

                            </tbody>
                        </table>
                        <form id="prescriptionForm">
                            <div class="row">
                                <div class="form-group mt-2 col-md-12">
                                    <label for="drug">Drug</label>
                                    <input type="text" class="form-control" id="drug" name="drug" placeholder="Enter drug" required>
                                </div>
                                <div class="form-group mt-2 col-md-6">
                                    <label for="qty">Qty</label>
                                    <input type="number" class="form-control" id="qty" name="qty"
                                        placeholder="Enter quantity" required>
                                </div>
                                <div class="form-group mt-2 col-md-6">
                                    <label for="number">Amount</label>
                                    <input type="text" class="form-control" id="amount" name="amount"
                                        placeholder="Enter amount" required>
                                </div>

                                <div class="form-group mt-2">
                                    <input type="submit" class="form-control btn btn-info" value="Add">
                                </div>
                            </div>
                            <br>
                            <!-- <div class="card-footer text-end">
                                <form action="{{ route('quotation.store', ['id' => $id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <button type="button" class="btn btn-success"  id="btn-sendQuote">Send quotation</button>
                                </form>
                            </div> -->
                        </form>
                    </div>
                </div>

                <div class="card-footer text-end">
                        <button type="button" onclick="sendQuotation()" id="btn-sendQuote" class="btn btn-success">Send quotation</button>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/cart-localstorage@1.1.4/dist/cart-localstorage.min.js" type="text/javascript"></script>
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Your JavaScript code using jQuery
    $(document).ready(function() {
        // Your code here
    });
</script>


<!-- Initialize Swiper -->
<script>
var swiper = new Swiper(".mySwiper", {
    spaceBetween: 10,
    slidesPerView: 5,
    freeMode: true,
    watchSlidesProgress: true,
});
var swiper2 = new Swiper(".mySwiper2", {
    spaceBetween: 10,
    thumbs: {
        swiper: swiper,
    },
});
</script>

<script>

    $(document).ready(function(){
        showCart()
    })

    const form = $('#prescriptionForm');
    form.on('submit', function(event) {
        event.preventDefault();

        const drug = $('#drug').val();
        const qty = $('#qty').val();
        const amount = $('#amount').val();

        cartLS.add({id: Date.now(), name: drug, price: amount}, qty)
        $('#prescriptionForm').trigger('reset');
        showCart()
    });

    function showCart(){
        const cart = $('#cart-body')
        const products = cartLS.list()

        let cartArea = ''
        products.map(product=>{
            cartArea+=`<tr>
                            <td>${product.name}</td>
                            <td>${Number(product.price).toFixed(2)} X ${Number(product.quantity).toFixed(2)}</td>
                            <td>${Number(product.price*product.quantity).toFixed(2)}</td>
                            <td><a href="#" class="btn btn-sm btn-danger" onclick="removeCartItem(${product.id})">Remove</a></td>
                        </tr>`
        })
        cartArea += `<tr>
                        <td colspan="4">Total: ${Number(cartLS.total()).toFixed(2)}</td>
                    </tr>`
        cart.html(cartArea)
    }

    function removeCartItem(id){
        cartLS.remove(id)
        showCart()
    }

    function sendQuotation(){
        let cartData = cartLS.list()

        if(cartData.length === 0)
        {
            alert('Quotation items required')
        }
        else{
            $(`#btn-sendQuote`).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...')
            $.ajax({
                url: "{{ route('quotation.store', ['id' => $id]) }}",
                method: "POST",
                dataType: 'json',
                data: {
                    cartData:cartData,
                    _token: '{{ csrf_token() }}',
                    total: cartLS.total(),
                        },
                success: function(response) {
                    alert('Quotation has been sent to the user')
                    cartLS.destroy()
                    window.location.href = "{{route('prescriptions.show')}}";
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        }
    }

    

</script>
@endsection
