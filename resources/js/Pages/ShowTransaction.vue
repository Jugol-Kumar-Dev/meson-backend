<template>
    <Layout>
        <Head title="Transaction List"/>
        <section class="app-user-list">
            <div class="container-lg">
                <div class="card">
                    <div class="card-body">
                        <div class="container mt-5">
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <h5>Bill To:</h5>
                                    <p>
                                        <strong>{{ trx?.user?.name  }}</strong><br>
                                        {{ trx?.order?.order_details?.full_address }}<br>
                                        Email: {{ trx?.user?.email }}<br>
                                        Phone: {{ trx?.user?.phone }}
                                    </p>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <h5>Trx_id: #{{ trx?.trx }}</h5>
                                    <p> Invoice Date: {{ moment(trx?.created_at).format('ll') }}<br>  </p>
                                    <p>Order Status: <span class="text-capitalize badge bg-light-success">{{ trx?.order?.status }}</span></p>
                                    <p>Order Status: <span class="text-capitalize badge bg-light-success">{{ trx?.status }}</span></p>
                                </div>
                            </div>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Total</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td>
                                        {{ trx?.order?.course?.name }}
                                    </td>
                                    <td>Description of Product 3</td>
                                    <td>{{ trx?.order?.total_amount }} ৳</td>
                                    <td>{{ trx?.order?.total_amount }} ৳</td>
                                </tr>
                                <tr class="table-danger">
                                    <th colspan="3" class="text-end">Subtotal</th>
                                    <td>{{ trx?.order?.total_amount }} ৳</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <td>{{ trx?.order?.total_amount }} ৳</td>
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
import Layout from "@/Shared/Layout.vue";
import moment from "moment";

let props = defineProps({
    trx: Object,
});


</script>
