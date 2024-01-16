<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pengguna Masuk - SchollName</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <!-- <link rel="stylesheet" href="/resources/style.css" {csp-style-nonce}> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css"  rel="stylesheet" />
</head>
<body class="antialiased bg-white dark:bg-gray-900">
    <div class="grid lg:grid-cols-2 bg-gray-200 dark:bg-gray-950">
        
        <div class="hidden lg:flex lg:flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="text-5xl dark:text-blue-300 ps-24"><?= (service('uri')->getSegment(3) === 'login') ? lang("Validation.users.auth.login.sambut") : lang("Validation.users.auth.register.sambut") ?></div>
        </div>
        <div class="">
            <div class="">
                <section class="bg-gray-50 dark:bg-gray-900">
                  <div class="flex flex-col w-full items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                          <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
                          Flowbite    
                      </a>
                      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                            <?php if(service('uri')->getSegment(3) === 'login') : ?>
                            
                              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                                  Sign in to your account
                              </h1>
                              <form method="post" class="space-y-4 md:space-y-6" action="<?= url_to('admin.auth', 'login') ?>">
                                <?= csrf_field() ?>
                                  <div>
                                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= lang("Validation.users.auth.login.inputLabelText") ?></label>
                                      <input type="text" name="username" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="<?= lang("Validation.users.auth.login.inputPlaceholderText") ?>" required="">
                                  </div>
                                  <div>
                                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                                  </div>
                                  <div class="flex items-center justify-between">
                                      <div class="flex items-start">
                                          <div class="flex items-center h-5">
                                            <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required="">
                                          </div>
                                          <div class="ml-3 text-sm">
                                            <label for="remember" class="text-gray-500 dark:text-gray-300">
                                                <?= lang("Validation.users.auth.login.inputRememberedText") ?>
                                            </label>
                                          </div>
                                      </div>
                                      <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500"><?= lang("Validation.users.auth.login.ForgetText") ?></a>
                                  </div>
                                  <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sign in</button>
                                  <?php if(session()->get("errors") !== null) : ?>
                                    <div id="alert-4" class="flex items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div class="ms-3 text-sm font-medium">
                                            <?= session()->get("errors") ?>  
                                        </div>
                                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-yellow-300 dark:hover:bg-gray-700" data-dismiss-target="#alert-4" aria-label="Close">
                                            <span class="sr-only">Close</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                        </button>
                                    </div>

                                  <?php endif; ?>
                                  
                                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                                      Don’t have an account yet? <a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign up</a>
                                  </p>
                              </form>
                              
                              <?php else : ?>
                                
                                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                                    Registration account
                                </h1>
                                <form method="post" class="space-y-4 md:space-y-6" action="<?= url_to('user.auth', 'register') ?>">
                                  <?= csrf_field() ?>
                                    <div>
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= lang("Validation.users.auth.register.inputLabelTextMail") ?></label>
                                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="<?= lang("Validation.users.auth.register.inputPlaceholderTextMail") ?>" required="">
                                    </div>
                                    <div>
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= lang("Validation.users.auth.register.inputLabelText") ?></label>
                                        <input type="text" name="username" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="<?= lang("Validation.users.auth.register.inputPlaceholderText") ?>" required="">
                                    </div>
                                    <div>
                                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                              <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required="">
                                            </div>
                                            <div class="ml-3 text-sm">
                                              <label for="remember" class="text-gray-500 dark:text-gray-300">
                                                  <?= lang("Validation.users.auth.register.inputRememberedText") ?>
                                              </label>
                                            </div>
                                        </div>
                                        <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500"><?= lang("Validation.users.auth.register.ForgetText") ?></a>
                                    </div>
                                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Regis in</button>
                                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                                        Do have an account yet? <a href="<?= url_to('user.auth', 'login') ?>" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Log In</a>
                                    </p>
                                </form>

                            <?php endif; ?>
                          </div>
                      </div>
                  </div>
                </section>
            </div>
        </div>
    </div>
<!-- <script type="module" src="/resources/script.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
</body>
</html>