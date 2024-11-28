<?php

namespace App\Command;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
        name: 'app:create-tags',
        description: 'Creates default tags for the Pinboard application.'
)]
class CreateTagsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tags = [
                "tag.new-business",
                "tag.business-update",
                "tag.service-launch",
                "tag.product-release",
                "tag.book-release",
                "tag.game-release",
                "tag.art-exhibit",
                "tag.music-release",
                "tag.event-workshop",
                "tag.event-conference",
                "tag.event-meetup",
                "tag.offer-discount",
                "tag.offer-coupon",
                "tag.announcement",
                "tag.community-project",
                "tag.nonprofit-cause",
                "tag.product-update",
                "tag.service-update",
                "tag.tech-launch",
                "tag.business-launch",
                "tag.sass-launch",
                "tag.website-launch",
                "tag.food-special",
                "tag.festival",
                "tag.event-online",
                "tag.hiring",
                "tag.fundraising",
                "tag.volunteer",
                "tag.opening-day",
                "tag.closing-sale",
                "tag.giveaway",
                "tag.promotion",
                "tag.local-event",
                "tag.global-event",
                "tag.educational-program",
                "tag.sports-event",
                "tag.personal-project",
                "tag.crowdfunding",
                "tag.new-feature",
                "tag.holiday-special",
                "tag.flash-sale",
                "tag.public-notice",
                "tag.social-event",
        ];

        foreach ($tags as $tagTitle) {
            // Check if the tag already exists
            $existingTag = $this->entityManager->getRepository(Tag::class)->findOneBy(['title' => $tagTitle]);
            if (!$existingTag) {
                $tag = new Tag($tagTitle);
                $this->entityManager->persist($tag);
                $output->writeln("Created tag: {$tagTitle}");
            } else {
                $output->writeln("Tag already exists: {$tagTitle}");
            }
        }

        $this->entityManager->flush();

        $output->writeln("All tags have been created or updated.");
        return Command::SUCCESS;
    }
}
