new Vue({
    el: '#app',
    data() {
        return {
            menus: [{
                label: 'TOP',
                path: './ex_index.html',
            },{
                label: 'PROGRAM',
                path: './ex_program.html'
            },{
                label: 'DATE',
                path: './ex_date.html',
            },{

            }],
            obj: [
                {id: 1, label: "date: 11月3日"},
                {id: 2, label: "time: 15:00-16:00"},
                {id: 3, label: "place: 市民ホール"}
        ],
            programs: [
                {id: 1, label: "カノン"},
                {id: 2, label: "四季"},
                {id: 3, label: "月光"}
            ]
        };
    }
});
