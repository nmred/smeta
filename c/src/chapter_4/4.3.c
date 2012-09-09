#include <stdio.h>

int main(void)
{
	long sum = 0L;
	int count = 0;
	
	printf("\nEnter the number of integer you wang to sum: ");
	scanf(" %d", &count);

	int i = 0;
	for (i = 1; i <= count; i++) {
		sum += i;	
	}

	printf("\nTotal of the first %d number is %ld\n", count, sum);

	return 0;
}
