new Vue({
    el: '#app',
    data() {
        return {
            number: '1',
            text:'受付番号ボタンを押してください'
        };
    },
    methods: {
        entry() {
            this.text= '受け付けました';
        }
    },
    computed: {
        isInValidnumber() {
            const number = this.number;
            const error = number.length >= 3 || isNaN(Number(number));
            return error;
        },
    }
});