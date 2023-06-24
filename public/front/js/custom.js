$(document).ready(function(){
    $("#sort").on("change",function(){
        // this.form.submit();
        var sort = $("#sort").val();
        var url = $("#url").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url:url,
            method:'Post',
            data:{sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            },error:function(){
                alert("Error");
            }
        });
    });

    $("#getPrice").change(function(){
        var size = $(this).val();
        var product_id = $(this).attr("product-id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url:'/get-product-price',
            data:{size:size,product_id:product_id},
            type:'post',
            success:function(resp){
                // alert(resp['final_price']);
                if(resp['discount']>0){
                    $(".getAttributePrice").html("<div class='price'><h4>Taka:"+resp['final_price']+"/-</h4></div><div class='original-price'><span>Original Price:</span><span>Taka:"+resp['product_price']+"/-</span></div>");
                }else{
                    $(".getAttributePrice").html("<div class='price'><h4>Taka:"+resp['final_price']+"/-</h4></div>");
                }
            },error:function(){
                alert("Error");
            }
        });
    });

    // update cart item quantity
    $(document).on('click','.updateCartItem',function(){
        if($(this).hasClass('plus-a')){
            // get quantity
            var quantity = $(this).data('qty');
            // ++ quantity
            new_qty = parseInt(quantity) + 1;
            // alert(new_qty);
        }
        if($(this).hasClass('minus-a')){
            // get quantity
            var quantity = $(this).data('qty');
            // check quantity atleast 1
            if(quantity<=1){
                alert("Item must be 1 or more");
                return false;
            }
            // -- quantity
            new_qty = parseInt(quantity) - 1;
            // alert(new_qty);
        }
        var cartid = $(this).data('cartid');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data:{cartid:cartid,qty:new_qty},
            url:'/cart/update',
            type:'post',
            success:function(resp){
                if(resp.status==false){
                    alert(resp.message);
                }
                $("#appendCartItems").html(resp.view);
            },error:function(){
                alert("error");
            }
        });
    });

    $(document).on('click','.deleteCartItem',function(){
        var cartid = $(this).data('cartid');
        var result = confirm("Are you sure!! Wants to delete???");
        if(result){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                data:{cartid:cartid},
                url:'/cart/delete',
                type:'post',
                success:function(resp){
                    $("#appendCartItems").html(resp.view);
                },error:function(){
                    alert("error");
                }
            });
        }
    });
});