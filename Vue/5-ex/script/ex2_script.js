new Vue({
    el: '#app',
    data() {
        return {
            number: '1234567',
            name: 'name'
           
        };
    },
    computed: {
        isInValidnumber() {
            const number = this.number;
            const isErr = number.length != 7 || isNaN(Number(number));
            return isErr;
        },
        isInValidName() {
            return this.name.length < 4;
        }
        }
});
