<template>

    <Layout>
        <Head title="Show Exam Details"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content-header-title mb-0">Exam Details</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="app-user-list">
            <div class="row">
                <div class="col-md">
                    <div class="card bg-white">
                        <div class="card-body">
                            <h2>{{ mock?.name }}</h2>
                            <p>Exam Time: {{ mock?.duration }}</p>
                            <p>Total Questions: {{ mock?.total_q }}</p>
                            <p>Questions Type: {{ mock?.question_type }}</p>
<!--                            <strong>Exam Password: {{ mock?.password }}</strong>-->
                            <hr>
                            <div>
                                <p style="white-space: pre-wrap">{{ mock?.about }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="card bg-white">
                        <div class="card-body">

                            <h2>Given Exam: {{ mock.users_count }} {{ mock.users_count > 1 ? 'Students' : 'Student' }}</h2>

                            <table class="table table-striped table-bordered">
                                <thead>
                                <th class="text-center p-2" scope="col">User</th>
                                <th class="text-center p-2" scope="col">Get Mark</th>
                                <th class="text-center p-2" scope="col">Rank</th>
                                <th class="text-center p-2" scope="col">Exam Date</th>
                                <th class="text-center p-2" scope="col">Action</th>
                                </thead>
                                <tbody>
                                <tr v-for="(user, index) in mock.users" :key="'single-user-data'+index">
                                    <td>
                                        <div class="d-flex align-items-start gap-3">
                                            <img :src="`https://ui-avatars.com/api/?background=random&name=${user?.user?.name}&rounded=true&size=50`" alt="">
                                            <div>
                                                <strong>{{ user?.user?.name }}</strong>
                                                <p class="mb-0">{{ user?.user?.email }}</p>
                                                <small>{{ user?.user?.phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ user?.mark }}</td>
                                    <td>{{ index+1 }}</td>
                                    <td>{{ moment(user?.created_at).format('lll') }}</td>
                                    <td>
                                        <Link :href="`/panel/mocktest/student-result/?id=${user?.id}`" class="btn btn-sm btn-warning">
                                            Show
                                        </Link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

<!--
            <h2 class="fw-bolder" v-if="mock.questions?.length">Mock Test Questions.</h2>
            <div class="row match-height" v-if="mock.questions?.length">
                <div class="col-md-6" v-for="ques in mock.questions" :key="`ques-${ques.id}`">
                    <div class="card bg-white">
                        <div class="card-body">
                            <strong>{{ ques?.question }}</strong>
                            <hr>
                            <p>Mark: {{ ques?.points }}</p>
                            <p>Category: {{ ques.category }}</p>
                            <p>Sub Category: {{ ques.sub_category }}</p>
                            <strong>Correct Answer: {{ ques?.correct }}</strong>
                            <hr>
                            <div class="list-group">
                                <div class="list-group-item" :class="{'bg-primary text-white' : 'answer_a' === 'answer_'+toLower(ques.correct)}">
                                    <h4>Answer A</h4>
                                    <div class="bg-gray shadow-md">{{ ques.answer_a }}</div>
                                </div>

                                <div class="list-group-item" :class="{'bg-primary text-white' : 'answer_b' === 'answer_'+toLower(ques.correct)}">
                                    <h4>Answer B</h4>
                                    <div class="bg-gray shadow-md">{{ ques.answer_b }}</div>
                                </div>

                                <div class="list-group-item" :class="{'bg-primary text-white' : 'answer_c' === 'answer_'+toLower(ques.correct)}">
                                    <h4>Answer C</h4>
                                    <div class="bg-gray shadow-md">{{ ques.answer_c }}</div>
                                </div>

                                <div class="list-group-item" :class="{'bg-primary text-white' : 'answer_d' === 'answer_'+toLower(ques.correct)}">
                                    <h4>Answer D</h4>
                                    <div class="bg-gray shadow-md">{{ ques.answer_d }}</div>
                                </div>

                                <div class="list-group-item" :class="{'bg-primary text-white' : 'answer_e' === 'answer_'+toLower(ques.correct)}">
                                    <h4>Answer E</h4>
                                    <div class="bg-gray shadow-md">{{ ques.answer_e }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
        </section>
    </Layout>

</template>

<script setup>
import Pagination from '@/Components/Pagination.vue';
import Icon from '@/Components/Icon.vue';
import Layout from "@/Shared/Layout.vue";
import moment from "moment";
import {toLower} from "lodash/string.js";
let {mock} = defineProps({
    mock: Object,
});

</script>
