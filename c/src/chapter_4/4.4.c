#include <stdio.h>

int main(void)
{
	long sum = 0L;
	int count = 0;
	int i = 0;

	printf("\nEnter the number of integers you want to sum: ");
	scanf(" %d", &count);
	
	for (i = 1; i <= count; sum += i++);
	
	printf("\nTotal of the first %d numbers is %ld\n", count, sum);
	return 0;	
}
