<template>
    <div>
        <h1>{{ timerCount }} Minutes Remaining</h1>
    </div>
</template>

<script setup>

//import {onMounted, ref} from "vue";
//
//    let data = {
//        minutes: "",
//        second: "",
//        startingMinuets : 10
//    }
//
//    let min = ref(0);
//    let sec = ref(0);
//
//    let updateCountdown = () =>{
//        let time = data.startingMinuets * 60
//        const minutes = Math.floor(time / 60)
//
//        let second = time % 60
//
//        second = second < 10 ? '0'+second : second
//        min.value = minutes
//        sec.value = second
//        time--
//    }
//
//    onMounted(()=>{
//        setInterval(updateCountdown, 1000);
//    })

</script>

<script>

export default {
    props:{
      mainTime:""
    },

    data() {
        return {
            timerCount:this.mainTime,
        }
    },

    watch: {
        timerCount: {
            handler(value) {
                if (value > 0) {
                    setTimeout(() => {
                        this.timerCount--
                    }, 1000 * 60);

                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        },
    }
}

</script>



<style scoped>

</style>
