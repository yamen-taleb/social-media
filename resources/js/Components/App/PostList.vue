<script setup>

import PostCreate from "@/Components/App/PostCreate.vue";
import PostItem from "@/Components/App/PostItem.vue";

const post1 = {
    user: {
        id: 1,
        name: "John Doe",
        avatar: "https://i.pravatar.cc/150?img=1"
    },
    group: null,
    attachments: null,
    description: "This is a sample post description.",
    create_at: "2023-10-01T12:00:00Z",

}
const post2 = {
    user: {
        id: 2,
        name: "Jane Smith",
        avatar: "https://i.pravatar.cc/150?img=2"
    },
    group: {
        id: 1,
        name: "Sample Group"
    },
    attachments: null,
    description: "This is another sample post description with more content to demonstrate the read more functionality. ".repeat(20),
    create_at: "2023-10-02T15:30:00Z",
}
const post3 = {
    user: {
        id: 3,
        name: "Alice Johnson",
        avatar: "https://i.pravatar.cc/150?img=3"
    },
    group: null,
    attachments: [
        {
            id: 1,
            url: "https://i.pravatar.cc/300?img=3",
            type: "image/png"
        },
        {
            id: 2,
            url: "https://i.pravatar.cc/300?img=3",
            type: "image/png"
        },
        {
            id: 3,
            name: "document.pdf",
            url: "https://i.pravatar.cc/350?img=3",
            type: "ima"
        },
        {
            id: 4,
            url: "https://i.pravatar.cc/300?img=3",
            type: "image/png"
        }
    ],
    description: "Short post.",
    create_at: "2023-10-03T09:45:00Z",
}
</script>

<template>
    <div>
        <PostCreate/>
        <div>
            <PostItem :post="post1"/>
            <PostItem :post="post2"/>
            <PostItem :post="post3"/>
        </div>
    </div>
</template>

<style scoped>

</style>
