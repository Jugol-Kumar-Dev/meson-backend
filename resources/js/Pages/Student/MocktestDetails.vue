<template>

    <Head title="Mocktest Enroll" />
    <section class="app-dashboard">
        <div class="alert alert-danger py-2 px-1" v-if="$page.props?.errors[0]">
            {{  $page.props?.errors[0] }}
        </div>

        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">{{ mocktest.name }}</h4>
                <h4 class="card-title">Duration: {{ mocktest.duration }} minutes</h4>
                <h4 class="card-title">Total Question: {{ mocktest.total_q }}</h4>


                <p class="pre-text">
                   {{ mocktest?.about }}
                </p>


                <a :href="`/mocktest-start/${mocktest?.id}`" class="btn btn-primary">Start now</a>

            </div>
        </div>
    </section>
</template>
<script>
import SLayout from './Layout.vue'
export default {
    layout: SLayout,
};
</script>
<script setup>

import {onMounted} from "vue";

let props = defineProps({
    mocktest: Object,
    url: String,
})

onMounted(()=>{
    if(localStorage.getItem('countDownDate')){
        localStorage.removeItem('countDownDate');
    }
})

</script>
<style>
.pre-text{
    word-break: break-word;
}
</style>
