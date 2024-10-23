<template>

    <Layout>
        <Head title="Exam Result List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content-header-title mb-0">Student Exam Result</h2>
                    </div>
                </div>
            </div>
        </div>

        <!--        {{ props.result }}-->

        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-body d-flex justify-content-between w-100">
                        <div class="w-50">
                            <h3 class="fw-bold">Exam Title: {{ props?.result?.mocktest?.name }}</h3>
                            <p>Duration: {{ props?.result?.mocktest?.duration }}</p>
                            <p>Total Questions: {{ props?.result?.mocktest?.total_q }}</p>
                            <p>Achived Mark: {{ props?.result?.mark }}</p>
                            <p>Position: {{ props?.result?.position }}Out Of {{ props?.result?.partisipants }}</p>
                            <p>Total Correct: {{ props.result?.total_correct }}</p>
                            <p>Total Incorrect: {{ props.result?.total_incaorrect }}</p>
                            <p>Given Exam Time: {{ moment(props.result?.created_at).format('lll') }}</p>
                        </div>
                        <div class="w-50  d-flex gap-2 align-items-start" style="border-left: 1px solid gray">
                            <img class="ms-5" style="width:75px"
                                 :src="`https://ui-avatars.com/api/?background=random&name=${props?.result?.user?.name}&rounded=true&size=50`"
                                 alt="">
                            <div class="ms-3">
                                <h3>Student Name: {{ props?.result?.user?.name }}</h3>
                                <p>Email: {{ props?.result?.user?.email }}</p>
                                <p>Phone: {{ props?.result?.user?.phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-10 mx-auto mt-8">
                <div class="bg-white p-4 rounded shadow-sm mt-4" v-for="(ans, i) in props.answers"
                     :key="`ans-${ans.id}`">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-1">
                        <div>
                            <h3 class="fw-semibold fs-5 text-dark">Question {{ i + 1 }}:</h3>
                            <p class="text-muted" v-html="ans?.question?.title"></p>
                        </div>
                        <div v-if="ans.user_answer !== null">
                            <p v-if="ans.user_answer === ans?.question?.answer"
                               class="bg-success text-white px-1 rounded-pill">
                                Correct Answer
                            </p>
                            <p v-else class="bg-danger text-white px-1 rounded-pill">
                                Wrong Answer
                            </p>
                        </div>
                        <p v-else class="bg-warning text-white px-1 rounded-pill">
                            No Select Answer
                        </p>
                    </div>
                    <div class="mb-1 d-flex flex-column gap-1">
                        <div v-for="option in ['a', 'b', 'c', 'd']" :key="option"
                             :class="{
            'bg-success bg-opacity-10 border-success': ans?.user_answer === ans.question.answer && ans?.user_answer === option || ans?.question.answer === option,
            'bg-danger bg-opacity-10 border-danger': ans.user_answer === option && ans?.user_answer !== ans.question.answer,
            'bg-white border': ans?.user_answer !== option && ans?.question.answer !== option
          }"
                             class="d-flex justify-content-between align-items-center p-1 border rounded">
                            <span v-html="ans?.question?.[option]"></span>
                            <i class="bi bi-check-circle-fill text-success" v-if="ans?.question?.answer === option"></i>
                            <i class="bi bi-x-circle-fill text-danger"
                               v-if="ans?.user_answer === option && ans?.question.answer !== option"></i>
                        </div>
                    </div>
                    <div class="mt-1">
                        <h6 class="fs-6 fw-semibold text-dark">Question Feedback:</h6>
                        <p class="bg-light p-1 rounded mt-2" v-html="ans?.question?.feedback"></p>
                    </div>
                </div>
            </div>
        </div>

    </Layout>

</template>

<script setup>
import Layout from "@/Shared/Layout.vue";
import moment from "moment";
import {computed} from 'vue'

let props = defineProps({
    answers: Object,
    result: Object,
});

</script>
<style>
p {
    margin: 0 !important;
    padding: 0 !important;
}
</style>
