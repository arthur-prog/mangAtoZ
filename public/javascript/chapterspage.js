let pasdispo = []

function AddPasDispo(chapitre){
    pasdispo.push(chapitre)
    console.log('coucou')
}

// console.log(pasdispo)
function Initialize() {
    let elements = document.getElementsByClassName('drawer');
    console.log(elements)
    let elements2 = elements[0].lastChild;
    let elements3 = elements2.children;
    let select = document.getElementsByClassName('mult-select-tag')

    select[0].addEventListener("click", function () {
        for (const child of elements3) {
            child.onclick = function (e) {

                let input = document.createElement('input')
                input.setAttribute('type', 'hidden')
                input.setAttribute('name', e.target.innerText)
                input.innerText = e.target.innerText;
                let form = document.getElementById('chapitre')
                form.appendChild(input)

                let items = document.getElementsByClassName('item-container')
                for (i = 0; i < items.length; i++) {
                    items[i].lastChild.setAttribute('id', items[i].firstChild.innerText)
                    items[i].lastChild.onclick = function () {
                        console.log(this.id)
                        const element = document.getElementsByName(this.id)
                        console.log(element)
                        element[0].parentElement.removeChild(element[0])
                    }
                }

            }

            let test = child.innerText;
            if (pasdispo.includes(Number(test))) {
                child.style.pointerEvents = 'none';
            }
        }
    })
}