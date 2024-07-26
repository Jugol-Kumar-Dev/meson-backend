<template>
    <Head title="Given Mock-Test"/>
    <section class="app-dashboard">
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">{{ mocktest.name }}</h4>
                <h4 class="card-title">Duration: {{ mocktest.duration }} minutes</h4>
                <h4 class="card-title">Total Question: {{ mocktest.total_q }}</h4>
            </div>
        </div>
        <div class="card position-fixed" style="
        top: 35%;
        position: fixed;
        z-index: 10;
        right: 0;">
            <div class="card-body">
                <h2 v-if="status">Remaining: <span id="minutes"></span> : <span id="seconds"></span></h2>
            </div>
        </div>
        <div class="card bg-light-primary"  v-if="!status">
            <div class="card-body">
                <h3 class="text-capitalize">Your Live Exam Time Is Over. Please check practice exams for further practice</h3>
            </div>
        </div>
    </section>
    <section v-if="status">
        <div class="row">
            <div class="col-12" v-for="(question, index) in ansForm.questions" :key="'single-item'+index">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Question-{{ index+1 }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text" v-html="question.title">
                        </p>
                        <ul class="list-group">
                            <li class="list-group-item">(A): <span v-html="question.a"></span></li>
                            <li class="list-group-item">(B): <span v-html="question.b"></span></li>
                            <li class="list-group-item">(C): <span v-html="question.c"></span></li>
                            <li class="list-group-item">(D): <span v-html="question.d"></span></li>
<!--                            <li class="list-group-item">(E): <span v-html="question.e"></span></li>-->
                        </ul>
                        <div class="d-flex">
                            <div class="mt-1">Answer: </div>
                            <div class="demo-inline-spacing px-3">
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                           :id="`a${question.id}`" value="a">
                                    <label class="form-check-label" :for="`a${question.id}`">Option A</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                           :id="`b${question.id}`" value="b">
                                    <label class="form-check-label" :for="`b${question.id}`">Option B</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                           :id="`c${question.id}`" value="c">
                                    <label class="form-check-label" :for="`c${question.id}`">Option C</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                           :id="`d${question.id}`" value="d">
                                    <label class="form-check-label" :for="`d${question.id}`">Option D</label>
                                </div>
<!--                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                           :id="`e${question.id}`" value="e">
                                    <label class="form-check-label" :for="`e${question.id}`">Option E</label>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <button class="btn btn-danger" :disabled="isLoading" @click="submitAnsForm">{{ isLoading ? 'Loading....' : 'Submit Answer' }}</button>
        </div>
    </section>
</template>

<script>
import SLayout from './Layout.vue'
import Icon from '@/Components/Icon.vue'

export default {
    components: {
        Icon
    },
    layout: SLayout,
};
</script>

<script setup>
import {ref, onMounted, onBeforeUnmount} from 'vue'
import MinutesCounter from '@/Components/MinutesCounter.vue'
import {useForm} from "@inertiajs/vue3";
let props = defineProps({
    status:Boolean,
    questions: Array|Boolean,
    mocktest: Object,
    url: String,
})

const isLoading = ref(false)

let ansForm = useForm({
    questions: props.questions,
    mocktest_id: props.mocktest.id,
})

let submitAnsForm = () => {
    isLoading.value=true
    ansForm.post(props.url, {
        onSuccess: () => {
            localStorage.removeItem('countDownDate');
            // return window.location.replace('/dashboard')
            isLoading.value = false
        }
    });
    localStorage.removeItem('countDownDate');
}


const duration = ref(props.mocktest.duration)

onMounted(()=> {
    if(props?.status){
        updateCountdown()
    }
})

// onBeforeUnmount(()=>{
//     if(props?.status){
//         submitAnsForm()
//         updateCountdown()
//     }
// })

function updateCountdown() {
    let countDownDate = localStorage.getItem('countDownDate');
    if (!countDownDate) {
        // data.value?.distanse * 60
        countDownDate = new Date().getTime() + (duration.value * 60 * 1000); // 15 days in milliseconds
        localStorage.setItem('countDownDate', countDownDate);
    }

    const x = setInterval(function() {
        const now = new Date().getTime();
        let distance = countDownDate - now;

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("minutes").innerText = minutes;
        document.getElementById("seconds").innerText = seconds;
        if (distance < 1) {
            clearInterval(x);
            submitAnsForm()
            localStorage.removeItem('countDownDate');
            // return window.location.replace('/mocktest')
        }
    }, 1000);
}




</script>
