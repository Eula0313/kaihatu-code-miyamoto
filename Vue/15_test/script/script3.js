new Vue({
    el: '#app',
    data() {
        return {
            day:0,
            radio:0,
            total:0
        };
    },
    methods: {
        increment() {
            this.day++;
        },
        decrement(){
            if(this.day > 0){
            this.day--;
            };
        },
        multiply(){
            this.total=this.fee*this.day;
            
        }
    }
});
