<?php

namespace OroCRM\Bundle\AccountBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;

/**
 * @codeCoverageIgnore
 */
class LoadIssuePriorityData extends AbstractTranslatableEntityFixture
{
    const ISSUE_PRIORITY_PREFIX = 'priority';

    /**
     * @var array
     */

    protected $issuePriorities = [
        IssuePriority::BLOCKER,
        IssuePriority::CRITICAL,
        IssuePriority::MAJOR,
        IssuePriority::MINOR,
        IssuePriority::TRIVIAL
    ];

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function loadEntities(ObjectManager $manager)
    {
        $issuePriorityRepository = $manager->getRepository('OroAcademicSimpleBTSBundle:IssuePriority');
        $translationLocales = $this->getTranslationLocales();

        foreach ($translationLocales as $locale) {
            foreach ($this->issuePriorities as $priorityName) {
                $issuePriority = $issuePriorityRepository->findOneBy(['name' => $priorityName]);
                if (!$issuePriority) {
                    $issuePriority = new IssuePriority($priorityName);
                }

                $issuePriorityLabel = $this->translate($priorityName, static::ISSUE_PRIORITY_PREFIX, $locale);
                $issuePriority
                    ->setLocale($locale)
                    ->setLabel($issuePriorityLabel);

                $manager->persist($issuePriority);
            }

            $manager->flush();
        }
    }
}
