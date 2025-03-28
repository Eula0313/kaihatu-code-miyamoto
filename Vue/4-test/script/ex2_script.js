new Vue({
    el: '#app',
    data() {
        return {
            tanka:0,
            ninzu:0,
            kei:0
        };
    },
    methods: {
        increment() {
            this.ninzu++;
        },
        decrement(){
            this.ninzu--;
        },
        multiply(){
            this.kei=this.tanka*this.ninzu;
            
        }
    },
    computed: {
        iskei(){
            return this.kei>10000;
        }
    }
});
