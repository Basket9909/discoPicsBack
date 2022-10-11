const alertBlock = document.querySelector('.container_flash .alert')
const alertBlockWidth = (alertBlock.clientWidth)
const alertBlockHeigth = (alertBlock.clientHeight)
const bar = alertBlock.querySelector('.bar')
let timer = 0
let time = 1000

alertBlock.style.left = ((window.innerWidth - alertBlockWidth - 50))+"px"
alertBlock.style.top = ((window.innerHeight- alertBlockHeigth -50))+"px"


// console.log(alertBlock.clientWidth)
// const test1 = (window.innerWidth*90)/100
// console.log(window.innerHeight)

setInterval(()=>{
    if(timer>=time) timeAlert()
    timer+=5
    bar.style.width =  timer/time*100 +"%"
},20)

const timeAlert = () =>{
    alertBlock.style.transition = 1 
    alertBlock.style.opacity = 0
}



// const myInterval = setInterval(timeAlert,3000)
