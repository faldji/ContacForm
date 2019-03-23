#ContactForm
<ol><li>
    Installation
    <ul><li>
        <code> composer install</code>
    </li>
    <li>
            <code> php bin/console doctrine:database:create</code>
         </li>
    <li>
        <code> php bin/console make:migration</code>
     </li>
     <li>
        <code> php bin/console doctrine:migrations:migrate</code>
     </li>
     <li>
        <code> php bin/console doctrine:fixtures:load</code>
     </li>
     <li>
        <code> php bin/console server:run</code>
          </li></ul>
    </li>
   </ol>
