<x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />

<x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required autocomplete="username" />

