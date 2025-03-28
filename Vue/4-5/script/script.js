new Vue({
    el: '#app',
    data() {
        return {
            name: 'name',
           
        };
    },
    computed: {
        isInValidName() {
            return this.name.length < 4;
        }
    }
});