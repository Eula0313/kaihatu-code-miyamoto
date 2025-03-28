new Vue({
    el: '#app',
    data() {
        return {
            ninzu: 20,
            name: 'name',
            obj: [
                { id: 1, label: "情報工学科" },
                { id: 2, label: "情報システム専攻科" },
                { id: 3, label: "情報システム科" }
            ]
        };
    },
    methods: {
        increment() {
            this.ninzu++;
        },
        decrement() {
            this.ninzu--;
        },
    },
});
