<template>
    <Head title="Reset Password" />
    <div class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static">
        <div class="app-content content ">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="auth-wrapper auth-basic px-2">
                        <div class="auth-inner my-2">
                            <!-- Login basic -->
                            <div class="card mb-0">
                                <div class="card-body">
                                    <a href="/" class="brand-logo">
                                        <img src="@/Images/logo2.png" class="img-fluid"/>
                                    </a>

                                    <!--                                    <h4 class="card-title text-center mb-1">Welcome to LMS Panel</h4>-->
                                    <p class="card-text text-center mb-2">Reset password</p>

                                    <div v-if="$page.props.flash.message" class="text-danger">
                                        <p class="text-danger" v-html="$page.props.flash.message"></p>
                                    </div>
                                    <form class="auth-login-form mt-2" @submit.prevent="submit">
                                        <Text v-model="form.email" type="email" label="Email"
                                              :error="form.errors.email"
                                              placeholder="mail@example.com" />

                                        <Password v-model="form.password" label="New Password" :error="form.errors.password" />
                                        <Password v-model="form.password_confirmation" label="Confirm Password" :error="form.errors.password_confirmation" />

                                        <button class="btn btn-primary w-100" tabindex="4" type="submit" :disabled="form.processing">Update</button>
                                    </form>
                                    <p class="text-center mt-2">
                                        <span>Have password?</span>
                                        <a href="/login">
                                            <span>Login</span>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <!-- /Login basic -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    layout: null,
};
</script>

<script setup>
import Password from '@/Components/form/Password.vue'
import Text from '@/Components/form/Text.vue'
import {useForm} from "@inertiajs/vue3";

let form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    errors:Object,
})
const submit = () => {
    form.post(route('updateNewPassword'), {
        onSuccess: () => alert("Password Reset Successfully Done..."),
        onFinish: () => form.reset('password'),
    });
};
</script>

<style lang="scss">
@import '../../../sass/base/pages/authentication.scss';

.card-top-design{
    border-top:8px solid var(--bs-primary);
}

</style>
