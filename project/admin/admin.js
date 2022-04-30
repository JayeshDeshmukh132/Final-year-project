let profile= document.querySelector('.navbar .profile');
document.querySelector('#user-btn').onclick=()=>{
    profile.classList.toggle('active');
}

window.onscroll = () =>{
    profile.classList.remove('active');
 }