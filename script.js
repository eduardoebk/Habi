
document.addEventListener('DOMContentLoaded',() => {
    new TypeIt(".span-interclasse",{
        speed: 200,
       
        loop: true
    }).type('campeonato',{delay:1}).delete(10)
    .type('intelasse',{delay:1}).pause(1).delete(5).type('rclasse',{dalay:1})
    
    
    .go()
})

const mode = document.getElementById('mode_icon');

mode.addEventListener('click', () => {
    const form = document.getElementById('login_form');

    if(mode.classList.contains('fa-moon')) {
        mode.classList.remove('fa-moon');
        mode.classList.add('fa-sun');

        form.classList.add('dark');
        return ;
    }
    
    mode.classList.remove('fa-sun');
    mode.classList.add('fa-moon');

    form.classList.remove('dark');
});

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const appendAlert = (message, type) => {
  const wrapper = document.createElement('div')
  wrapper.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')

  alertPlaceholder.append(wrapper)
}

const alertTrigger = document.getElementById('login_button')
if (alertTrigger) {
  alertTrigger.addEventListener('click', () => {
    appendAlert('Ops,sua senha ou email estão incorreto', 'Erro')
  })
}