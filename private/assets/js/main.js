location.href = "#Center"

const slilder = setInterval(()=>{
    const imgDivs = document.querySelectorAll('.selected-img');
    imgDivs.forEach(div=>{
        div.classList.add('move');
    })
}, 1000);

const correction = setInterval(()=>{
    const imgDivs = document.querySelectorAll('.move');
    imgDivs.forEach(div=>{
        if (div.classList.contains('s1')) {
            div.classList.add('s2');
            div.firstChild.innerHTML = 'Grownups'
            div.classList.remove('s1');
        }else if(div.classList.contains('s2')) {
            div.classList.add('s3');
            div.firstChild.innerHTML = 'Angular Momentum'
            div.classList.remove('s2');
        }else if(div.classList.contains('s3')) {
            div.classList.add('s4');
            div.firstChild.innerHTML = 'Circuit Diagram'
            div.classList.remove('s3');
        }else if(div.classList.contains('s4')) {
            div.classList.add('s5');
            div.firstChild.innerHTML = 'Alternative Energy Revolution'
            div.classList.remove('s4');
        }else if(div.classList.contains('s5')) {
            div.classList.add('s1');
            div.firstChild.innerHTML = 'self description'
            div.classList.remove('s5');
        }
        div.classList.remove('move');
    })

}, 2000);