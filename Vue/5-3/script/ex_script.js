new Vue({
    el: '#app',
    data() {
        return {
            menus: [{
                label: 'TOP',
                path: './ex_index.html',
                icon: 'fas fa-crow'
            },{
                label: 'ABOUT',
                path: './about.html',
                icon: 'fas fa-home'
            },{
                label: 'SCHEDULE',
                path: './schedule.html',
                icon: 'fas fa-calendar' 
            },{
                label: 'CONTACT',
                path: './contact.html',
                icon: 'fas fa-envelope'
            }]
        };
    }
});
