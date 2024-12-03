<?php

namespace App\Enum;

enum ReactionTypeEnum: string
{
    // Positive Reactions
    case INNOVATIVE = 'Innovative';
    case GOOD_QUALITY = 'Good Quality';
    case HELPFUL = 'Helpful';
    case WORTH_CHECKING_OUT = 'Worth Checking Out';
    case PROFESSIONAL = 'Professional';
    case EXCITING = 'Exciting';

    // Constructive or Neutral Reactions
    case NEEDS_MORE_INFORMATION = 'Needs More Information';
    case NOT_CLEAR = 'Not Clear';
    case TOO_GENERIC = 'Too Generic';
    case CONFUSING_PRICING = 'Confusing Pricing';
    case UNCLEAR_PURPOSE = 'Unclear Purpose';
    case INTERESTING_IDEA = 'Interesting Idea';

    // Negative Reactions
    case OVERPRICED = 'Overpriced';
    case POOR_DESIGN = 'Poor Design';
    case NOT_RELEVANT = 'Not Relevant';
    case LOW_VALUE = 'Low Value';
    case MISLEADING = 'Misleading';

    // Interactive Reactions
    case I_WANT_THIS = 'I Want This';
    case WILL_RECOMMEND = 'Will Recommend';
    case IM_INTERESTED = 'I’m Interested';
    case COLLABORATION_OPPORTUNITY = 'Collaboration Opportunity';

}
