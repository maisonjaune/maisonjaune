<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

class <?= $class_name ?> extends AdminCRUD
{
    public function configurationList(ConfigurationListInterface $configurationList): void
    {
        // TODO: Configure your list
    }

    public function getEntityClass(): string
    {
        return <?= $entity_class_name ?>::class;
    }

    public function getControllerClass(): string
    {
        return <?= $controller_class_name ?>::class;
    }

    public function getFormType(): string
    {
        // TODO: Set your FormType
    }

    public function getRouterPrefix(): string
    {
        return '<?= $router_prefix ?>';
    }
}