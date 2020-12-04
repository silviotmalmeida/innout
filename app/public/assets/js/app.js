
//utilização de função anônima autoinvocada
(function () {
	
	//ao clicar no objeto toogle, o menu lateral deve aparecer/desaparecer
	
	//linkando com o objeto toogle
	const menuToggle = document.querySelector('.menu-toggle')

	//definindo a ação do objeto toogle
	menuToggle.onclick = function (e) {
	
		//linkando com o body
		const body = document.querySelector('body')

		//o metodo classList.toggle ativa e desativa uma determinada classe do objeto
		//ativa/desativa a classe hide-sidebar no body
		body.classList.toggle('hide-sidebar')
	}
})()

function activateClock() {
    const activeClock = document.querySelector('[active-clock]')
    if(!activeClock) return

    function addOneSecond(hours, minutes, seconds) {
        const d = new Date()
        d.setHours(parseInt(hours))
        d.setMinutes(parseInt(minutes))
        d.setSeconds(parseInt(seconds) + 1)
    
        const h = `${d.getHours()}`.padStart(2, 0)
        const m = `${d.getMinutes()}`.padStart(2, 0)
        const s = `${d.getSeconds()}`.padStart(2, 0)
    
        return `${h}:${m}:${s}`
    }

    setInterval(function() {
        // '07:27:19' => ['07', '27', '19']
        const parts = activeClock.innerHTML.split(':')
        activeClock.innerHTML = addOneSecond(...parts)
    }, 1000)
}

activateClock()