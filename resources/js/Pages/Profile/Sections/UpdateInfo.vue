<script setup>
import Container from "../../../Components/Container.vue";
import Title from "../../../Components/Title.vue";
import InputField from "../../../Components/InputField.vue";
import PrimaryBtn from "../../../Components/PrimaryBtn.vue";
import SessionMessages from "../../../Components/SessionMessages.vue";
import ErrorMessages from "../../../Components/ErrorMessages.vue";
import { router, useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    status: String,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const resendEmail = (e) => {
    router.post(
        route("verification.send"),
        {},
        {
            onStart: () => {
                e.target.disabled = true;
            },
            OnFinish: () => {
                e.target.disabled = false;
            },
        }
    );
};
</script>

<template>
    <Container class="mb-6">
        <div class="mb-6">
            <Title>Update Information</Title>
            <p>Update your accounts's profile information and email address</p>
        </div>

        <ErrorMessages :errors="form.errors"  />

        <form
            @submit.prevent="form.patch(route('profile.info'))"
            class="space-y-6"
        >
            <InputField
                label="Name"
                icon="id-badge"
                class="w-1/2"
                v-model="form.name"
            />

            <InputField
                label="Email"
                icon="at"
                class="w-1/2"
                v-model="form.email"
            />

            <SessionMessages :status="status" />
            <div
                v-if="user.email_verified_at === null"
                class="flex items-center gap-2"
            >
                <p>Your Email Address is unverified.</p>
                <button
                    @click="resendEmail"
                    class="text-indigo-500 font-medium underline dark:text-indigo-400 dark:hover:text-indigo-500 disabled:text-slate-400 disabled:cursor-wait"
                >
                    Click here to resend the verification email.
                </button>
            </div>

            <PrimaryBtn :disabled="form.processing">Save</PrimaryBtn>
        </form>
    </Container>
</template>
