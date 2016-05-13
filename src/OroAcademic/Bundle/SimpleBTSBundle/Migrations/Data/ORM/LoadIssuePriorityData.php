<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Migrations\Data\ORM;

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
        10 => IssuePriority::BLOCKER,
        20 => IssuePriority::CRITICAL,
        30 => IssuePriority::MAJOR,
        40 => IssuePriority::MINOR,
        50 => IssuePriority::TRIVIAL
    ];

    /**
     * {@inheritdoc}
     */
    public function loadEntities(ObjectManager $manager)
    {
        $priorityRepository = $manager->getRepository('OroAcademicSimpleBTSBundle:IssuePriority');
        $translationLocales = $this->getTranslationLocales();

        foreach ($translationLocales as $locale) {
            foreach ($this->issuePriorities as $order => $priorityName) {
                $issuePriority = $priorityRepository->findOneBy(['name' => $priorityName]);
                if (!$issuePriority) {
                    $issuePriority = new IssuePriority($priorityName);
                }

                $issuePriorityLabel = $this->translate($priorityName, static::ISSUE_PRIORITY_PREFIX, $locale);
                $issuePriority
                    ->setLocale($locale)
                    ->setLabel($issuePriorityLabel)
                    ->setPriorityOrder($order);

                $manager->persist($issuePriority);
            }

            $manager->flush();
        }
    }
}
