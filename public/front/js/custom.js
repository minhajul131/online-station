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

    //delete cart item
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

    // register form validation for customer/user
    $("#registerForm").submit(function(){
        $(".loader").show();
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data:formdata,
            url:'/user/register',
            type:'post',
            success:function(resp){
                if(resp.type=="error"){
                    $(".loader").hide();
                    $.each(resp.errors,function(i,error){
                        $("#register-"+i).attr('style','color:red');
                        $("#register-"+i).html(error);
                        setTimeout(function(){
                            $("#register-"+i).css({'display':'none'});
                        },5000)
                    })
                }else if(resp.type=="success"){
                    $("#register-success").attr('style','color:green');
                    $("#register-success").html(resp.message);
                    $(".loader").hide();
                    setTimeout(function(){
                        $("#register-success").css({'display':'none'});
                    },5000)
                }
            },error:function(){
                alert("error");
            }
        })
    });

    // update account info form for customer/user
    $("#accountForm").submit(function(){
        $(".loader").show();
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data:formdata,
            url:'/user/account',
            type:'post',
            success:function(resp){
                if(resp.type=="error"){
                    $(".loader").hide();
                    $.each(resp.errors,function(i,error){
                        $("#account-"+i).attr('style','color:red');
                        $("#account-"+i).html(error);
                        setTimeout(function(){
                            $("#account-"+i).css({'display':'none'});
                        },5000)
                    })
                }else if(resp.type=="success"){
                    $("#account-success").attr('style','color:green');
                    $("#account-success").html(resp.message);
                    $(".loader").hide();
                    setTimeout(function(){
                        $("#account-success").css({'display':'none'});
                    },5000)
                }
            },error:function(){
                alert("error");
            }
        })
    });

    // update password for customer/user
    $("#passwordForm").submit(function(){
        $(".loader").show();
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data:formdata,
            url:'/user/update-password',
            type:'post',
            success:function(resp){
                if(resp.type=="error"){
                    $(".loader").hide();
                    $.each(resp.errors,function(i,error){
                        $("#password-"+i).attr('style','color:red');
                        $("#password-"+i).html(error);
                        setTimeout(function(){
                            $("#password-"+i).css({'display':'none'});
                        },5000)
                    })
                }else if(resp.type=="incorrect"){
                    $(".loader").hide();
                    $("#password-error").attr('style','color:red');
                    $("#password-error").html(resp.message);
                    setTimeout(function(){
                        $("#password-error").css({'display':'none'});
                    },5000)
                }else if(resp.type=="success"){
                    $("#password-success").attr('style','color:green');
                    $("#password-success").html(resp.message);
                    $(".loader").hide();
                    setTimeout(function(){
                        $("#password-success").css({'display':'none'});
                    },5000)
                }
            },error:function(){
                alert("error");
            }
        })
    });

    // login form validation for customer/user
    $("#loginForm").submit(function(){
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data:formdata,
            url:'/user/login',
            type:'post',
            success:function(resp){
                if(resp.type=="error"){
                    $.each(resp.errors,function(i,error){
                        $("#login-"+i).attr('style','color:red');
                        $("#login-"+i).html(error);
                        setTimeout(function(){
                            $("#login-"+i).css({'display':'none'});
                        },5000)
                    })
                }else if(resp.type=="incorrect"){
                    $("#login-error").attr('style','color:red');
                    $("#login-error").html(resp.message);
                }else if(resp.type=="inactive"){
                    $("#login-error").attr('style','color:red');
                    $("#login-error").html(resp.message);
                }else if(resp.type=="success"){
                    window.location.href = resp.url;
                }
            },error:function(){
                alert("error");
            }
        })
    });

    // forgot password form for customer/user
    $("#forgotForm").submit(function(){
        $(".loader").show();
        var formdata = $(this).serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            data:formdata,
            url:'/user/forgot-password',
            type:'post',
            success:function(resp){
                if(resp.type=="error"){
                    $(".loader").hide();
                    $.each(resp.errors,function(i,error){
                        $("#forgot-"+i).attr('style','color:red');
                        $("#forgot-"+i).html(error);
                        setTimeout(function(){
                            $("#forgot-"+i).css({'display':'none'});
                        },5000)
                    })
                }else if(resp.type=="success"){
                    $("#forgot-success").attr('style','color:green');
                    $("#forgot-success").html(resp.message);
                    $(".loader").hide();
                    setTimeout(function(){
                        $("#forgot-success").css({'display':'none'});
                    },5000)
                }
            },error:function(){
                alert("error");
            }
        })
    });
});