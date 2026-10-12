Feature: CakePHP table return types
  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
      <psalm errorLevel="1" autoloader="../bootstrap.php">
        <projectFiles>
          <directory name="."/>
          <directory name="../Fixture"/>
          <ignoreFiles>
            <directory name="../../vendor"/>
          </ignoreFiles>
        </projectFiles>
        <plugins>
          <pluginClass class="LordSimal\CakePHPPsalm\Plugin" />
        </plugins>
      </psalm>
      """

  Scenario: infer application tables from locator and controller aliases
    Given I have the following code
      """
      <?php
      use Cake\Controller\Controller;
      use Cake\ORM\Locator\LocatorInterface;

      function articleTitles(LocatorInterface $locator, Controller $controller): string
      {
          return $locator->get('Articles')->articleTitle()
              . $controller->fetchTable('Articles')->articleTitle();
      }
      """
    When I run Psalm
    Then I see no errors
    And I see exit code 0

  Scenario: retain CakePHP table type for unknown and dynamic aliases
    Given I have the following code
      """
      <?php
      use Cake\Controller\Controller;
      use Cake\ORM\Locator\LocatorInterface;
      use Cake\ORM\Table;

      function unknownTable(LocatorInterface $locator): Table
      {
          return $locator->get('Unknown');
      }

      function dynamicTable(Controller $controller, string $alias): Table
      {
          return $controller->fetchTable($alias);
      }

      function defaultTable(Controller $controller): Table
      {
          return $controller->fetchTable();
      }
      """
    When I run Psalm
    Then I see no errors
    And I see exit code 0
