<template>

    <Head title="Exam List" />
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Exam List {{ timerCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <section class="app-order-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table">
                    <thead class="table-light">
                    <tr class="">
                        <th class="sorting">name</th>
                        <th class="sorting">Total Questions</th>
                        <th class="sorting">Time Details</th>
                        <th class="sorting">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="mocktest in allMOcks" :key="mocktest.id">
                        <td>{{ mocktest?.mock?.name }}</td>
                        <td>{{ mocktest?.mock.total_q ?? mocktest?.questionCount }}</td>
                        <td>
                            <div class="d-flex flex-column" v-if="mocktest?.mock?.exam_type === 'main' && mocktest?.mock?.start_time && mocktest?.mock?.end_time">
                                <span>Start Time: {{ moment( mocktest?.mock?.start_time)?.format('lll') }}</span>
                                <span>End Time: {{ moment(mocktest?.mock?.end_time)?.format('lll') }}</span>
                                <span>Duration: {{ mocktest?.mock?.duration }} Min</span>
                            </div>
                            <div v-else>
                                {{ mocktest?.mock?.duration }} Min
                            </div>
                        </td>
                        <td>
                            <a class="btn btn-success" v-if="!mocktest.user" :href="`/mocktest/${mocktest.mock.id}`">Start now</a>
                            <a class="btn btn-primary" :href="`/mocktest-result/${mocktest.mock.id}`">Show Result</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- list and filter end -->
    </section>
</template>

<script>
import SLayout from './Layout.vue'
export default {
    layout: SLayout,
};
</script>

<script setup>
    import {computed } from 'vue'
    import moment from "moment/moment.js";
    import Icon from "@/Components/Icon.vue";

    let props = defineProps({
        mocktests: Object,
    });

    const allMOcks = computed(()=>{

        const key = 'title';
        return [...new Map(props.mocktests.map(item =>
            [item.mock[key], item])).values()];

    })






</script>
