new Vue({
    el: '#app',
    data() {
        return {
            text:'あそう　はなこ'
        };
    },
    methods: {
        nameChange() {
            this.text= 'Aso Hanako';
        }
    }
});