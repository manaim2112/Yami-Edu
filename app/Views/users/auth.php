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
            <div class="text-5xl dark:text-blue-300 ps-24"><?= ($lOrR == 'login') ? lang("Validation.users.auth.login.sambut") : lang("Validation.users.auth.register.sambut") ?></div>
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
                              <form method="post" class="space-y-4 md:space-y-6" action="<?= url_to('user.auth', 'login') ?>">
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
</body>
</html>