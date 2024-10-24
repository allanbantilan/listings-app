<script setup>
import Container from "../../Components/Container.vue";
import { router } from "@inertiajs/vue3";
const props = defineProps({
    listing: Object,
    user: Object,
    canModify: Boolean,
});

const deleteListing = () => {
    if (confirm("Are you sure?")) {
        router.delete(route("listing.destroy", props.listing.id));
    }
};

const toggleApprove = () => {
    let msg = props.listing.approved
        ? "Disapproved this Listing?"
        : "Approved this Listing?";

    if (confirm(msg)) {
        router.put(route("admin.approve", props.listing.id));
    }
};
</script>

<template>
    <Head title="- Listing Detail" />

    <!-- admin -->
    <div
        v-if="$page.props.auth.user.role == 'admin'"
        class="bg-slate-800 text-white mb-6 p-6 rounded-md font-medium flex items-center justify-between"
    >
        <p>
            This listing is {{ listing.approved ? "Approved" : "Disapproved" }}
        </p>

        <button @click.prevent="toggleApprove" class="bg-slate-600 px-3 py-1 rounded-md">
            {{ listing.approved ? "Disapprove it" : "Approve it" }}
        </button>
    </div>

    <Container class="flex gap-4">
        <div class="w-1/4 rounded-md overflow-hidden">
            <img
                :src="
                    listing.image
                        ? `/storage/${listing.image}`
                        : '/storage/images/listing/default.jpg'
                "
                class="w-full h-full object-cover object-center"
                alt=""
            />
        </div>

        <div class="w-3/4">
            <!-- listing info -->
            <div class="mb-6">
                <div class="flex items-end justify-between mb2">
                    <p class="text-slate-400 w-full border-b mb-2">
                        Listing Detail
                    </p>

                    <!-- edit and delete button  -->
                    <div v-if="canModify" class="pl-4 flex items-center gap-4">
                        <Link
                            :href="route('listing.edit', listing.id)"
                            class="bg-green-500 rounded-md text-white px-6 py-2 hover:outline outline-green-500 outline-offset-2"
                            >Edit
                        </Link>

                        <button
                            @click="deleteListing"
                            class="bg-red-500 rounded-md text-white px-6 py-2 hover:outline outline-green-500 outline-offset-2"
                        >
                            delete
                        </button>
                    </div>
                </div>

                <h3 class="font-bold text-2xl mb-4">{{ listing.title }}</h3>
                <p>{{ listing.desc }}</p>
            </div>

            <!-- contact info  -->
            <div class="mb-6">
                <p class="text-slate-400 w-full border-b mb-2">Contact Info</p>

                <!-- email  -->
                <div v-if="listing.email" class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-at"></i>
                    <p>Email:</p>
                    <a :href="`mailto:${listing.email}`" class="text-link">
                        {{ listing.email }}</a
                    >
                </div>

                <!-- link  -->
                <div v-if="listing.link" class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-up-right-from-square"></i>
                    <p>Link:</p>
                    <a :href="listing.link" target="_blank" class="text-link">
                        {{ listing.email }}</a
                    >
                </div>
                <!-- user  -->

                <div class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-user"></i>
                    <p>Listed By:</p>
                    <Link
                        :href="route('home', { user_id: user.id })"
                        class="text-link"
                    >
                        {{ user.name }}
                    </Link>
                </div>
            </div>

            <!-- tags  -->
            <div v-if="listing.tags" class="mb-6">
                <p class="text-slate-400 w-full border-b mb-2">Tags</p>

                <div class="flex items-center gap-3">
                    <div v-for="tag in listing.tags.split(',')" :key="tag">
                        <Link
                            :href="route('home', { tag })"
                            class="bg-slate-500 text-white px-2 py-py rounded-full hover:bg-slate-700 dark:hover:bg-slate-900"
                        >
                            {{ tag }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </Container>
</template>
