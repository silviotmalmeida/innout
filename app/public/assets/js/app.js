
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


//função responsável por atualizar contiuamente o relógio
function activateClock() {
	
	//linkando com o objeto que contenha a classe active-clock
    const activeClock = document.querySelector('[active-clock]')

	//se não existir tal classe, encerra a função
    if(!activeClock) return

	//função responsável por adicionar 1 segundo a um horário passado por argumento
	function addOneSecond(hours, minutes, seconds) {
		
		//criando um objeto Date
        const d = new Date()

		//setando a hora e minuto recebidos por argumento
        d.setHours(parseInt(hours))
        d.setMinutes(parseInt(minutes))

		//setando o segundo recebido por argumento e incrementando em 1
        d.setSeconds(parseInt(seconds) + 1)
    
		//populando as variáveis de saída
        const h = `${d.getHours()}`.padStart(2, 0)
        const m = `${d.getMinutes()}`.padStart(2, 0)
        const s = `${d.getSeconds()}`.padStart(2, 0)
    
		//retornando o horário incrementado em 1 segundo
        return `${h}:${m}:${s}`
    }

	//função de loop infinito a cada 1000ms
    setInterval(function() {

		//obtendo o array com hora, minuto e segundo presentes no html
        const parts = activeClock.innerHTML.split(':')

		//setando no html os novos valores de hora, minuto e segundo
        activeClock.innerHTML = addOneSecond(...parts)
    }, 1000)
}

//ativando a atualização automática do relógio
activateClock()