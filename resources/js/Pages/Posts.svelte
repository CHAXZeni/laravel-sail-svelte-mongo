<script>
    import { useForm } from "@inertiajs/svelte";
    import { flip } from "svelte/animate";
    import { fly } from "svelte/transition";
    import { cubicOut } from "svelte/easing";

    export let posts;

    let form = useForm({
        message: null,
    });

    function submit() {
        $form.post("/", {
            onSuccess: () => $form.reset(),
        });
    }

    window.Echo.channel("new-post").listen("NewPost", (e) => {
        posts.unshift(e.post);
        posts.pop();
        posts = posts;
    });
</script>

<div
    class="flex flex-col h-screen items-center justify-center w-full gap-6 font-mono"
>
    <h1 class="font-bold text-4xl text-green-800/90">The Post Pit</h1>
    <div class="flex flex-row items-center w-full h-2/3">
        <div class="w-1/3 h-full z-50 bg-white" />
        <div
            class="flex flex-col items-center shadow-[inset_0px_0px_9px_0px_rgb(0,0,0,1)] w-1/3 h-full p-3"
        >
            {#each posts as post (post._id)}
                <div
                    class="flex items-center bg-green-900/40 justify-center text-2xl h-1/5 border-2 rounded-md w-full"
                    animate:flip
                    transition:fly={{ x: "100%", duration: 2000, opacity: 0 }}
                >
                    {post.message}
                </div>
            {/each}
        </div>
        <div class="w-1/3 h-full z-50 bg-white" />
    </div>
    <form
        class="flex flex-col justify-center items-center w-1/3"
        on:submit|preventDefault={submit}
    >
        <div class="flex gap-3 w-full justify-center items-center">
            <input
                class="border-black/30 border-2 rounded-md p-1 focus-visible:outline-none focus-visible:border-black/60 w-2/3"
                type="text"
                bind:value={$form.message}
            />
            <button
                class="rounded-md bg-green-800 text-white py-2 px-3 font-bold hover:bg-green-950/80"
                type="submit"
                disabled={$form.processing}>Post</button
            >
        </div>
        {#if $form.errors.message}
            <div>{$form.errors.message}</div>
        {/if}
    </form>
</div>
