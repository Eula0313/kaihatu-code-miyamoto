new Vue({
    el: '#app',
    data() {
        return {
            count:0
        };
    },
    methods: {
        increment() {
            this.count++;
        },
        decrement(){
            this.count--;
        }
    }
});