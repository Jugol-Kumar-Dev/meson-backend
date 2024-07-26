<template>
    <div>
        <h3>Time Left</h3>
        {{ day }} day :
        {{ hour }} hour :
        {{ minute }} min :
        {{ second }} second
    </div>
</template>


<script>
export default {

    data(){
        return {
            day:0,
            hour:0,
            minute:0,
            second:0,
        }
    },
    computed: {
        _mainsecond: () => 1000,
        _mainminute(){
            return this._mainsecond * 60;
        },
        _mainhour(){
            return this._mainminute * 60;
        },
        _mainday(){
            return this._mainhour * 24
        }


    },
    methods: {
        showRemaning(){
            let timer = setInterval(() =>{

                let now = new Date();
                let end = new Date(2022, 9, 0, 12, 10, 10);
                let distance = end.getTime() - now.getTime()

                if(distance < 0){
                    clearInterval(timer)
                    return
                }

                let _days = Math.floor(distance / this._mainday)
                let _hours = Math.floor((distance % this._mainday) / this._mainhour)
                let _minuets = Math.floor((distance % this._mainhour) / this._mainminute)
                let _seconds = Math.floor((distance % this._mainminute) / this._mainsecond)

                this.minute = _minuets < 10 ? '0' +_minuets  : _minuets
                this.second = _seconds < 10 ? '0' + _seconds : _seconds
                this.hour   = _hours < 10 ? '0' + _hours : _hours
                this.day    = _days < 10 ? '0' + _days : _days
            }, 1000)
        }
    },

    mounted() {
        this.showRemaning();
    }
}

</script>


<style lang="css" scoped>

</style>
