new Vue({
    el: '#app',
    data() {
    return {
        menus: [{
            label: 'TOP',
            path: './index.html'
        },{
            label: 'ABOUT',
            path: './about.html'
        },{
            label: 'SCHEDULE',
            path: './schedule.html'
        },{
            label: 'CONTACT',
            path: './contact.html'
        }]
    };
}
});