// INTIALIZING ALL THE VARIABLES
let loginform= document.querySelector('.login-form');
let resplogin=document.querySelector('.login-form');
let orders=document.querySelector('.my-orders');
let resporders=document.querySelector('.my-orders');
let shoppingcart=document.querySelector('.shopping-cart');
let respshoppingcart= document.querySelector('.shopping-cart');
let ham=document.querySelector('.left-nav');
let bar=document.querySelector('#bars-resp');
let close=document.querySelector('#close');
// ..............................................
// Login box
document.querySelector('#login').onclick= ()=>{
    loginform.classList.toggle('active');
    shoppingcart.classList.remove('active');
    orders.classList.remove('active');
}
document.querySelector('#login-resp').onclick = () =>{
    resplogin.classList.toggle('active');
    resporders.classList.remove('active');
    respshoppingcart.classList.remove('active');
}
document.querySelector('#login-close').onclick= () =>{
    loginform.classList.remove('active');
}
// ..............................................
// ..............................................
// ORDERS
document.querySelector('#order').onclick=() =>{
    orders.classList.toggle('active');
    shoppingcart.classList.remove('active');
    // searchbox.classList.remove('active');
    loginform.classList.remove('active');    
}

document.querySelector('#order-resp').onclick= () =>{
    resporders.classList.toggle('active');
    respshoppingcart.classList.remove('active');
    // respsearchbox.classList.remove('active');
    resplogin.classList.remove('active');
}

document.querySelector('#order-close').onclick= () =>{
    orders.classList.remove('active');
}
// ....................................
// SHOPPING CART
document.querySelector('#shop').onclick = () =>{
    shoppingcart.classList.toggle('active');
    orders.classList.remove('active');
    loginform.classList.remove('active');
}
document.querySelector('#shop-resp').onclick = () =>{
    respshoppingcart.classList.toggle('active');
    resporders.classList.remove('active');
    resplogin.classList.remove('active');
}
document.querySelector('#shopping-close').onclick = () =>{
    shoppingcart.classList.remove('active');
}

// ..............................................
// RESPONSIVE NAVBAR
if(bar){
    bar.addEventListener('click', () => {
        ham.classList.add('hamburger');
        resplogin.classList.remove('active');
        respshoppingcart.classList.remove('active');
        resporders.classList.remove('active');
    })
}   
if(close){
    close.addEventListener('click', () => {
        ham.classList.remove('hamburger');
    })
} 
// ..........................................................
