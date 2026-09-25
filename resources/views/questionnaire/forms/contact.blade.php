<div class="grid gap-5 sm:grid-cols-2">

    <div>
        <label for="first_name"
               class="mb-2 block text-sm font-medium text-zinc-800">
            First name
        </label>

        <input
            id="first_name"
            name="first_name"
            type="text"
            placeholder="Your first name"
            class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 hover:border-zinc-300 focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5"
        >
    </div>

    <div>
        <label for="last_name"
               class="mb-2 block text-sm font-medium text-zinc-800">
            Last name
        </label>

        <input
            id="last_name"
            name="last_name"
            type="text"
            placeholder="Your last name"
            class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 hover:border-zinc-300 focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5"
        >
    </div>

</div>


{{-- Email --}}
<div>
    <label for="email"
           class="mb-2 block text-sm font-medium text-zinc-800">
        Email address
    </label>

    <input
        id="email"
        name="email"
        type="email"
        placeholder="you@company.com"
        class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 hover:border-zinc-300 focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5"
    >
</div>


{{-- Subject --}}
<div>
    <label for="subject"
           class="mb-2 block text-sm font-medium text-zinc-800">
        Subject
    </label>

    <div class="relative">
        <select
            id="subject"
            name="subject"
            class="w-full appearance-none rounded-xl border border-zinc-200 bg-white px-4 py-3.5 text-sm text-zinc-700 outline-none transition hover:border-zinc-300 focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5"
        >
            <option value="">Select a topic</option>
            <option value="support">Technical support</option>
            <option value="sales">Sales</option>
            <option value="partnership">Partnership</option>
            <option value="feedback">Feedback</option>
            <option value="other">Other</option>
        </select>

        <svg
            class="pointer-events-none absolute right-4 top-1/2 size-4 -translate-y-1/2 text-zinc-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.8"
                d="M6 9l6 6 6-6"
            />
        </svg>
    </div>
</div>


{{-- Message --}}
<div>
    <label for="message"
           class="mb-2 block text-sm font-medium text-zinc-800">
        Message
    </label>

    <textarea
        id="message"
        name="message"
        rows="6"
        placeholder="How can we help you?"
        class="w-full resize-none rounded-xl border border-zinc-200 bg-white px-4 py-3.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 hover:border-zinc-300 focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5"
    ></textarea>
</div>
