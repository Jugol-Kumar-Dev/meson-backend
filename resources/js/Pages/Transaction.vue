<template>
    <Layout>
        <Head title="Transaction List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Transaction List</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <div class="d-flex justify-content-between align-items-center header-actions mx-0 px-3 mt-75 mb-5">
                        <div class="d-flex justify-content-center justify-content-lg-start">
                            <div class="select-search-area">
                                <label>Show <select class="form-select" v-model="perPage">
                                    <option :value="undefined">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label>
                            </div>
                        </div>
                        <div>
                            <strong>Total Revinew: <span class="text-success">{{ props?.total }} ৳</span></strong>
                        </div>
                        <div class="ps-xl-75 ps-0">

                        </div>
                    </div>
                    <table class="user-list-table table">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">TRX</th>
                            <th class="sorting">Method</th>
                            <th class="sorting">Date</th>
                            <th class="sorting">Amount</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="transaction in transactions.data" :key="transaction.id">
                            <td>{{ transaction.trx }}</td>
                            <td>{{ transaction.method }}</td>
                            <td>{{ transaction.date }}</td>
                            <td>{{ transaction.amount }}</td>
                            <td>
                                <div>

                                    <Link :href="`/panel/show-transactions/${transaction.trx}`"
                                          class="btn btn-sm btn-primary d-flex justify-content-center align-items-center gap-1">
                                        <vue-feather type="eye" size="12"/>
                                        <span>Show</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="transactions.links" :from="transactions.from" :to="transactions.to"
                                :total="transactions.total"/>
                </div>
            </div>
            <!-- list and filter end -->
        </section>
    </Layout>
</template>

<script setup>
import Pagination from '@/Components/Pagination.vue';
import {ref, watch} from 'vue';
import debounce from "lodash/debounce";
import Layout from "@/Shared/Layout.vue";
import {router} from "@inertiajs/vue3";

let props = defineProps({
    transactions: Object,
    filters: Object,
    total: Object,
    url: String,
});


let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage], debounce(function ([val, val2]) {
    router.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
}, 300));

</script>
