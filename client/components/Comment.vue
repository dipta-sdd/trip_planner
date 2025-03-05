<template>
    <article class="">
        <div class="flex items-start space-x-3">
            <div
                class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold">
                {{ comment?.name.charAt(0) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-center mb-1">
                    <h3 class="text-sm font-medium text-gray-900">{{ comment?.name }}</h3>
                    <span class="text-xs text-gray-500">2 hours ago</span>
                </div>
                <div class="text-sm text-gray-700 mb-3">
                    <p>{{ comment.text }}</p>
                </div>
                <div class="mb-4">
                    <div class="flex space-x-2 flex-wrap">
                        <input v-model="text" type="text" placeholder="Write a reply..."
                            class="flex-1 min-w-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button @click="replyComment(comment.id)"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Reply
                        </button>
                    </div>
                </div>

                <details v-if="comment?.replies?.length" class="ml-6 pl-4 border-l-2 border-gray-200">
                    <summary class="text-sm text-blue-600 cursor-pointer mb-2 hover:text-blue-800">
                        Show replies
                    </summary>
                    <div class="space-y-2">
                        <Comment :tripId="tripId" v-for="reply in Object.values(comment.replies)" :key="reply.id" :comment="reply" @reply="emit('reply', $event)" />
                    </div>
                </details>
            </div>
        </div>
    </article>
</template>
<script setup>
const props = defineProps({
    comment: {
        type: Object,
        required: true
    },
    tripId:{
        type: Number,
        required: true
    }
})
const emit = defineEmits(['reply']);
const text = ref('');
const replyComment = async (commentId) => {
    try {
        const response = await $fetch('http://localhost:8000/api/comments/'+props.tripId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: {
                text: text.value,
                parent_id: commentId
            }
        });
        // console.log(response);
        text.value = '';
        emit('reply', response);
    } catch (error) {
        console.error(error);
        emit('reply', {commentId, text: text.value});
    }
}
</script>