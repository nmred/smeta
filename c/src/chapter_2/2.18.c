#include <stdio.h>

int main(void)
{
	long shorty = 0L;
	long lofty = 0L;
	long feet = 0L;
	
	long inches = 0L;
	long shorty_to_lofty = 0L;
	long lofty_to_tree = 0L;
	long tree_height = 0;
	const long inches_to_feet = 12L;
	
	printf("Enter lofty's height to the top of his/her head. in whole feet: ");
	scanf("%ld", &feet);
	printf("... and then inches: ");
	scanf("%ld", &inches);
	lofty = feet * inches_to_feet + inches;
	
	printf("Enter Shorty's height up to his/her eyes, in whole feet: ");
	scanf("%ld", &feet);
	printf("... and then inches: ");
	scanf("%ld", &inches);
	shorty = feet * inches_to_feet + inches;

	printf("Enter the distance between Shorty and Lofty, in whole feet:");
	scanf("%ld", &feet);
	printf("... and then inches: ");
	scanf("%ld", &inches);
	shorty_to_lofty = feet * inches_to_feet + inches;

	printf("Finally enter the distance to the tree to the nearest foot:");
	scanf("%ld", &feet);
	lofty_to_tree = feet * inches_to_feet;

	tree_height = shorty + (shorty_to_lofty + lofty_to_tree)*(lofty - shorty) / shorty_to_lofty;

	printf("The height of the tree is %ld feet and %ld inches.\n", tree_height / inches_to_feet, tree_height % inches_to_feet);
	return 0;
}
