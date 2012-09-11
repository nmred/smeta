#include <stdio.h>

int main(void)
{
	int count1 = 1;
	do {
		int count2 = 0;
		++count2;
		printf("\ncount1 = %d count2 = %d", count1, count2);	
	} while(++count1 <= 8);

	printf("\ncount1 = %d\n", count1);

	return 0;
}
