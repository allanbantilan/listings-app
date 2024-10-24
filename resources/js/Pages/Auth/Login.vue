<script setup>
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import TextLink from "../../Components/TextLink.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryBtn from "../../Components/PrimaryBtn.vue";
import { useForm } from "@inertiajs/vue3";
import ErrorMessages from "../../Components/ErrorMessages.vue";
import Checkbox from "../../Components/Checkbox.vue";
import SessionMessages from "../../Components/SessionMessages.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: null,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="- Login" />
    <Container class="w-1/2">
        <div class="mb-8 text-center">
            <Title>Log in to your Account</Title>
            <p>
                Need an Account?
                <TextLink routeName="register" label="Register" />
            </p>
        </div>

        <ErrorMessages :errors="form.errors" />
        <SessionMessages :status="status" />

        <form @submit.prevent="submit" class="space-y-6">
            <InputField label="Email" icon="at" v-model="form.email" />

            <InputField
                label="Password"
                type="password"
                icon="key"
                v-model="form.password"
            />

            <div class="flex items-center justify-between">
                <Checkbox name="remember" v-model="form.remember">
                    Remember Me</Checkbox
                >
                <TextLink
                    routeName="password.request"
                    label="Forgot Password?"
                ></TextLink>
            </div>

            <div class="flex justify-center">
                <PrimaryBtn :disabled="form.processing">Login</PrimaryBtn>
            </div>
        </form>
    </Container>
</template>
