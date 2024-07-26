<template>

    <Layout>
        <Head title="Mocktest List" />
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="mb-0">
                            Result
                        </h2>
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
                                <tr v-for="(user, index) in results" :key="user.name+user.id">
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
                                        <Link v-if="user.user_id === $page.props.auth.user.id" :href="`/mocktest/single-result/${user?.id}`" class="btn btn-sm btn-warning">
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
        </section>

    </Layout>
</template>

<script setup>
import moment from "moment";
import Layout from "@/Pages/Student/Layout.vue";

let props = defineProps({
    mock:Object,
    results: Object,
});




</script>
