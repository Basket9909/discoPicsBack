const container = document.querySelector('.images_container_publication')

const hovers = container.querySelectorAll('.container_size_img .container_hover_img')



const containerGalerie = document.querySelector('.container_zoom_galerie')
const closeGalerie = containerGalerie.querySelector('.close_img')
const galerie = document.querySelector('.zoom_galerie')

hovers.forEach((hover)=>{

    hover.querySelector('.elarge_img_link').addEventListener('click',()=>{
        containerGalerie.style.display = 'block'
        galerie.style.display = 'block'
        galerie.querySelector('img').setAttribute('src',hover.dataset.url)
        galerie.querySelector('img').setAttribute('alt',hover.dataset.alt)
    })
})

closeGalerie.addEventListener('click',()=>{
    containerGalerie.style.display= 'none'
    galerie.querySelector('img').setAttribute('src','')
})